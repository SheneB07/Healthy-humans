<?php
session_start();

require_once 'connection.php';
require_once 'lang.php';

$pickupNumber = isset($_SESSION['pickupNumber']) ? (int)$_SESSION['pickupNumber'] : null;

if ($pickupNumber === null) {
    http_response_code(400);
    echo 'No pickup number found.';
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT o.order_id, o.pickup_number, o.price AS line_total, o.quantity, o.datetime, o.dineChoice,
               p.product_id, p.name
        FROM orders o
        INNER JOIN products p ON p.product_id = o.ordered_product
        WHERE o.pickup_number = :pickup
        ORDER BY o.order_id ASC
    ");
    $stmt->execute([':pickup' => $pickupNumber]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Failed to load receipt.';
    exit;
}

if (!$rows) {
    http_response_code(404);
    echo 'Order not found.';
    exit;
}

$first = $rows[0];
$datetime = (string)($first['datetime'] ?? '');
$total = 0.0;

$sessionDine = $_SESSION['diningOption'] ?? null;
if ($sessionDine === 'TakeOut') {
    $dineText = t('receipt.dine_out', 'Take out');
} else {
    // default to dine in
    $dineText = t('receipt.dine_in', 'Eat in');
}

$items = [];
foreach ($rows as $r) {
    $qty = (int)($r['quantity'] ?? 1);
    $lineTotal = (float)($r['line_total'] ?? 0);
    $total += $lineTotal;
    $productId = (int)($r['product_id'] ?? 0);
    $name = (string)($r['name'] ?? '');
    $items[] = [
        'product_id' => $productId,
        'name' => t('product.name.' . (string)$productId, $name),
        'qty' => $qty,
        'line_total' => $lineTotal,
    ];
}

$receiptDataForJs = [
    'pickupNumber' => $pickupNumber,
    'datetime' => $datetime,
    'dineLabel' => t('receipt.dine_label', 'Dining'),
    'dineText' => $dineText,
    'items' => $items,
    'total' => $total,
    'shopName' => t('receipt.shop_name', 'Healthy Humans'),
    'thanks' => t('receipt.thanks', 'Thank you!'),
    'totalLabel' => t('receipt.total', 'Total'),
];
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(getCurrentLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('receipt.title', 'Receipt')); ?></title>
    <style>
        body{
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #FCF5DA;
        }
        .wrap{
            max-width: 420px;
            margin: 0 auto;
        }
        .actions{
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
        }
        .btn{
            flex: 1;
            padding: 12px 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            background: #8CD003;
            color: #222;
        }
        .btn.secondary{
            background: #FFB181;
        }
        .status{
            margin: 10px 0 14px;
            background: #fff;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
        }
        .receipt{
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        pre{
            margin: 0;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
            line-height: 1.35;
            white-space: pre-wrap;
            word-break: break-word;
        }

        @media print{
            body{ background: #fff; padding: 0; }
            .actions, .status{ display: none; }
            .receipt{ box-shadow: none; border-radius: 0; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="actions">
            <button class="btn" id="btnPrintBrowser"><?= htmlspecialchars(t('receipt.print_browser', 'Print receipt')); ?></button>
            <button class="btn secondary" id="btnPrintUsb"><?= htmlspecialchars(t('receipt.print_usb', 'Print via USB')); ?></button>
        </div>

        <div class="status" id="status"><?= htmlspecialchars(t('receipt.status_ready', 'Ready to print')); ?></div>

        <div class="receipt">
            <pre id="receiptPreview"></pre>
        </div>
    </div>

    <script>
        const RECEIPT = <?= json_encode($receiptDataForJs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        function moneyEUR(amount){
            try{
                return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'EUR' }).format(amount);
            }catch{
                return 'EUR ' + (Math.round(amount * 100) / 100).toFixed(2);
            }
        }

        function padRight(str, len){
            str = String(str);
            return str.length >= len ? str : (str + ' '.repeat(len - str.length));
        }

        function padLeft(str, len){
            str = String(str);
            return str.length >= len ? str : (' '.repeat(len - str.length) + str);
        }

        function buildPlainReceipt(){
            const width = 42;
            const line = '-'.repeat(width);

            const title = RECEIPT.shopName || 'Healthy Humans';
            const pickup = '#' + RECEIPT.pickupNumber;
            const dt = RECEIPT.datetime ? String(RECEIPT.datetime) : '';

            let out = '';
            out += title + '\n';
            out += line + '\n';
            out += padRight('Pickup', 12) + padLeft(pickup, width - 12) + '\n';
            if (RECEIPT.dineLabel && RECEIPT.dineText) {
                const left = String(RECEIPT.dineLabel).slice(0, 12);
                out += padRight(left, 12) + padLeft(String(RECEIPT.dineText), width - 12) + '\n';
            }
            if (dt) out += dt + '\n';
            out += line + '\n';

            (RECEIPT.items || []).forEach(item => {
                const qty = (item.qty || 1) + 'x ';
                const name = String(item.name || '').trim();
                const price = moneyEUR(Number(item.line_total || 0));
                const left = (qty + name).slice(0, width - 12);
                out += padRight(left, width - 12) + padLeft(price, 12) + '\n';
            });

            out += line + '\n';
            out += padRight((RECEIPT.totalLabel || 'Total') + ':', width - 12) + padLeft(moneyEUR(Number(RECEIPT.total || 0)), 12) + '\n';
            out += '\n' + (RECEIPT.thanks || 'Thank you!') + '\n\n';
            return out;
        }

        function setStatus(msg){
            const el = document.getElementById('status');
            if (el) el.textContent = msg;
        }

        const previewEl = document.getElementById('receiptPreview');
        if (previewEl) previewEl.textContent = buildPlainReceipt();

        document.getElementById('btnPrintBrowser')?.addEventListener('click', () => window.print());

        // WebUSB (Xprinter / ESC-POS), based on xprint.html example.
        let selectedDevice = null;
        const PRINTER_VENDORS = [0x0483, 0x04b8, 0x0456, 0x067b];

        async function autoDetectPrinter(){
            if (!navigator.usb) return false;
            const devices = await navigator.usb.getDevices();
            const printer = devices.find(d => PRINTER_VENDORS.includes(d.vendorId));
            if (printer){
                selectedDevice = printer;
                return true;
            }
            return false;
        }

        function buildEscPosReceipt(){
            // Init, center, then content, then cut.
            return "\x1B\x40" +
                "\x1B\x61\x01" + (RECEIPT.shopName || 'Healthy Humans') + "\n" +
                "\x1B\x61\x00" +
                "------------------------------------------\n" +
                "Pickup: #" + RECEIPT.pickupNumber + "\n" +
                ((RECEIPT.dineLabel && RECEIPT.dineText) ? ((String(RECEIPT.dineLabel) + ": " + String(RECEIPT.dineText)) + "\n") : "") +
                (RECEIPT.datetime ? (String(RECEIPT.datetime) + "\n") : "") +
                "------------------------------------------\n" +
                (RECEIPT.items || []).map(i => {
                    const name = String(i.name || '').trim();
                    const qty = (i.qty || 1) + "x ";
                    const price = moneyEUR(Number(i.line_total || 0)).replace('€', 'EUR').replace(/\s/g,' ');
                    return (qty + name).slice(0, 30).padEnd(30, ' ') + price + "\n";
                }).join('') +
                "------------------------------------------\n" +
                (RECEIPT.totalLabel || 'Total') + ": " + moneyEUR(Number(RECEIPT.total || 0)).replace('€', 'EUR') + "\n\n" +
                (RECEIPT.thanks || 'Thank you!') + "\n\n\n" +
                "\x1D\x56\x00";
        }

        async function printUSB(){
            try{
                if (!navigator.usb){
                    setStatus('WebUSB not supported in this browser.');
                    return;
                }

                setStatus('Connecting to printer...');

                if (!selectedDevice){
                    await autoDetectPrinter();
                }
                if (!selectedDevice){
                    const filters = PRINTER_VENDORS.map(vendorId => ({ vendorId }));
                    selectedDevice = await navigator.usb.requestDevice({ filters });
                }
                if (!selectedDevice){
                    setStatus('No printer selected.');
                    return;
                }

                await selectedDevice.open();
                if (selectedDevice.configuration === null) {
                    await selectedDevice.selectConfiguration(1);
                }

                try { await selectedDevice.claimInterface(0); } catch {}

                const encoder = new TextEncoder();
                const escpos = buildEscPosReceipt();
                const intf = selectedDevice.configuration.interfaces[0].alternates[0];
                const endpoint = intf.endpoints.find(e => e.direction === 'out');
                if (!endpoint) throw new Error('Output endpoint not found');

                await selectedDevice.transferOut(endpoint.endpointNumber, encoder.encode(escpos));
                setStatus('Printed successfully.');

                setTimeout(() => { try{ selectedDevice.close(); }catch{} }, 800);
            }catch(e){
                setStatus('USB print failed: ' + (e && e.message ? e.message : String(e)));
            }
        }

        document.getElementById('btnPrintUsb')?.addEventListener('click', printUSB);
    </script>

    <script src="assets/js/fullscreen.js"></script>
</body>
</html>

