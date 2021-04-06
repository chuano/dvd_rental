<?php

declare(strict_types=1);

namespace App\Shared\Infra;


use App\Modules\Rental\User\Domain\Exception\InvalidTokenException;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\JwtService;
use App\Shared\Domain\Uuid;
use Firebase\JWT\JWT;
use Throwable;

class JwtServiceImpl implements JwtService
{
    private const TOKEN_DURATION = 86400;

    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function encode(Credentials $credentials): string
    {
        $timestamp = date("U");
        $arrayPayload = [
            'id' => $credentials->getId()->getValue(),
            'profile' => $credentials->getProfile(),
            'iat' => (int)$timestamp,
            'exp' => $timestamp + self::TOKEN_DURATION,
        ];

        return JWT::encode($arrayPayload, $this->secret, 'HS256');
    }

    /**
     * @throws InvalidTokenException
     */
    public function decode(string $jwt): Credentials
    {
        try {
            $decoded = JWT::decode($jwt, $this->secret, ['HS256']);
            return new Credentials(Uuid::create($decoded->id), $decoded->profile);
        } catch (Throwable) {
            throw new InvalidTokenException();
        }
    }
}
