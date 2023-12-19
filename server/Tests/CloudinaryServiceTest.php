<?php

namespace __test__;

use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\ArrayUtils;
use core\HttpStatus;
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
    private MockObject $uploadApiMock;


    public function setUp(): void
    {
        parent::setUp();
        $this->uploadApiMock = $this->getMockBuilder(UploadApi::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->cloudinaryService = new CloudinaryService($this->uploadApiMock);
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

    public function testUploadFileFail()
    {
        $file = [
            'name' => 'example.jpg',
            'size' => 500000,
        ];
        $options = [];

        $this->uploadApiMock
            ->method('upload')
            ->with($this->equalTo($file), $this->equalTo($options));

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(CloudinaryResponse::UPLOAD_FILE_FAIL->value);

        $this->cloudinaryService->uploadFile($file, $options);
    }

    public function testDeleteFileFail()
    {
        $public_id = "sds";
        $options = [];

        $this->uploadApiMock
            ->method('destroy')
            ->with($this->equalTo($public_id), $this->equalTo($options))->willThrowException(
                new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR, CloudinaryResponse::DELETE_FILE_FAIL->value)
            );

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(CloudinaryResponse::DELETE_FILE_FAIL->value);

        $this->cloudinaryService->deleteFile($public_id, $options);
    }

    public function testDeleteSuccess()
    {
        $public_id = "sds";
        $options = [];

        $this->uploadApiMock
            ->method('destroy')
            ->with($this->equalTo($public_id), $this->equalTo($options));

        $result = $this->cloudinaryService->deleteFile($public_id, $options);

        $this->assertNull($result);
    }
}
