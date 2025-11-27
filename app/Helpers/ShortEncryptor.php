<?php

namespace App\Helpers;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class ShortEncryptor
{
    protected const BASE62 = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    protected static function getSecretMap(): string
    {
        $seed = env('CUSTOM_ENCRYPTION_SEED', 'default-secret');

        if (strlen($seed) < 16) {
            throw new \RuntimeException('Encryption seed must be at least 16 characters');
        }

        $chars = str_split(self::BASE62);
        $oldSeed = mt_rand();
        mt_srand(crc32($seed));
        shuffle($chars);
        mt_srand($oldSeed);

        return implode('', $chars);
    }

    public static function encrypt(?int $id, bool $raw = false)
    {
        try {
            if (empty($id) || $id < 1) {
                throw new \InvalidArgumentException("Invalid ID ({$id}) — must be a positive integer.");
            }

            $map = self::getSecretMap();
            $encoded = self::base62Encode($id, $map);
            $padChar = $map[0];
            $token = str_pad($encoded, 8, $padChar, STR_PAD_LEFT);

            return $raw
                ? ['success' => true, 'data' => $token]
                : $token;
        } catch (Throwable $e) {
            return self::handleError('Encryption Failed', $e, ['input_id' => $id], $raw);
        }
    }

    public static function decrypt(?string $hash, bool $raw = false)
    {
        try {
            if (empty($hash) || strlen($hash) !== 8) {
                throw new \InvalidArgumentException("Invalid encrypted hash: '{$hash}'");
            }

            $map = self::getSecretMap();
            $padChar = $map[0];
            $trimmed = ltrim($hash, $padChar);

            if (empty($trimmed)) {
                throw new \InvalidArgumentException("Hash '{$hash}' contains only padding characters");
            }

            $id = self::base62Decode($trimmed, $map);

            if ($id < 1) {
                throw new \InvalidArgumentException("Decrypted value '{$id}' is invalid (<=0)");
            }

            return $raw
                ? ['success' => true, 'data' => $id]
                : $id;
        } catch (Throwable $e) {
            return self::handleError('Decryption Failed', $e, ['input_hash' => $hash], $raw);
        }
    }

    protected static function base62Encode(int $value, string $map): string
    {
        $result = '';
        do {
            $result = $map[$value % 62] . $result;
            $value = intdiv($value, 62);
        } while ($value > 0);
        return $result;
    }

    protected static function base62Decode(string $encoded, string $map): int
    {
        $value = 0;
        for ($i = 0; $i < strlen($encoded); $i++) {
            $pos = strpos($map, $encoded[$i]);
            if ($pos === false) {
                throw new \InvalidArgumentException("Invalid character '{$encoded[$i]}' in encoded string");
            }
            $value = $value * 62 + $pos;
        }
        return $value;
    }

    /**
     * Handles encryption/decryption errors differently for local and production environments.
     */
    protected static function handleError(string $title, Throwable $e, array $context = [], bool $raw = false)
    {
        $env = config('app.env');
        $isLocal = ($env === 'local');
        $isApi = request()->expectsJson();

        // Always log the full error for developers
        Log::error("[ShortEncryptor] {$title}", [
            'environment' => $env,
            'message' => $e->getMessage(),
            'type' => get_class($e),
            'context' => $context,
        ]);

        // Raw mode (developer testing)
        if ($raw) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'debug' => [
                    'exception' => get_class($e),
                    'context' => $context,
                    'environment' => $env
                ]
            ];
        }

        // API / JSON responses
        if ($isApi) {
            return response()->json([
                'success' => false,
                'message' => $isLocal
                    ? $e->getMessage()
                    : ($title === 'Encryption Failed'
                        ? 'Encryption failed: Invalid or missing ID.'
                        : 'Decryption failed: Invalid or expired token.'),
                'type' => $isLocal ? get_class($e) : null,
                'context' => $isLocal ? $context : null,
            ], 500);
        }

        // LOCAL ENV: detailed developer view
        if ($isLocal && View::exists('errors.encryption')) {
            echo View::make('errors.encryption', [
                'title' => $title,
                'error' => [
                    'message' => $e->getMessage(),
                    'type' => get_class($e),
                    'context' => $context,
                    'environment' => $env,
                ],
            ])->render();
            exit;
        }

        // PRODUCTION ENV: short, simple user-understandable message
        $simpleMessage = match ($title) {
            'Encryption Failed' => 'Unable to process encryption — the provided ID is invalid.',
            'Decryption Failed' => 'Unable to process decryption — the provided code is invalid or expired.',
            default => 'Something went wrong while processing your request.',
        };

        abort(500, $simpleMessage);
    }
}
