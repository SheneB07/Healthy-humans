<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/connection.php';

function getCurrentLanguage(): string
{
    $option = $_SESSION['languageOption'] ?? 'English';

    if ($option === 'Dutch') {
        return 'nl';
    }

    return 'en';
}

function t(string $key, ?string $default = null): string
{
    static $cache = [];

    $lang = getCurrentLanguage();
    $cacheKey = $lang . '|' . $key;

    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }

    global $pdo;

    try {
        $stmt = $pdo->prepare(
            'SELECT text FROM translations WHERE language = :language AND `key` = :key LIMIT 1'
        );
        $stmt->execute([
            ':language' => $lang,
            ':key'      => $key,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && isset($row['text'])) {
            $cache[$cacheKey] = $row['text'];
            return $row['text'];
        }
    } catch (Throwable $e) {
        // If the translations table does not exist or another error occurs,
        // fall back to the provided default or the key.
    }

    if ($default !== null) {
        return $default;
    }

    // Fallback: try English if current language is not English
    if ($lang !== 'en') {
        try {
            $stmt = $pdo->prepare(
                'SELECT text FROM translations WHERE language = :language AND `key` = :key LIMIT 1'
            );
            $stmt->execute([
                ':language' => 'en',
                ':key'      => $key,
            ]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && isset($row['text'])) {
                $cache['en|' . $key] = $row['text'];
                return $row['text'];
            }
        } catch (Throwable $e) {
            // Ignore and continue to final fallback.
        }
    }

    return $default ?? $key;
}

