<?php

namespace __test__;

use modules\cloudinary\CloudinaryService;
use modules\office\OfficeRepository;
use modules\seat\dto\CreateSeatDto;
use modules\seat\SeatService;
use modules\seat\SeatRepository;
use modules\user\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use shared\enums\CloudinaryResponse;
use shared\enums\OfficeResponse;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

class CloudinaryServiceTest extends TestCase
{
    private CloudinaryService $cloudinaryService;

    public function setUp(): void
    {
        parent::setUp();
        $this->cloudinaryService = new CloudinaryService();
    }

    public function testUploadFileSizeFail()
    {
        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(CloudinaryResponse::FILE_TOO_LARGE->value);

        $this->cloudinaryService->uploadFile(["size" => 10000000, "name" => "image.jpg"]);
    }

    public function testUploadFileTypeFail()
    {
        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(CloudinaryResponse::FILE_WRONG_FORMAT->value);

        $this->cloudinaryService->uploadFile(["size" => 20000, "name" => "image.php"]);
    }
}
