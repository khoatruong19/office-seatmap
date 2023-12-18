<?php

namespace __test__;

use modules\office\OfficeRepository;
use modules\seat\dto\CreateSeatDto;
use modules\seat\SeatService;
use modules\seat\SeatRepository;
use modules\user\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use shared\enums\OfficeResponse;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

class SeatServiceTest extends TestCase
{
    private SeatService $seatService;
    private MockObject $seatRepositoryMock;
    private MockObject $officeRepositoryMock;
    private MockObject $userRepositoryMock;


    public function setUp(): void
    {
        parent::setUp();
        $this->seatRepositoryMock = $this->createMock(SeatRepository::class);
        $this->officeRepositoryMock = $this->createMock(OfficeRepository::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->seatService = new SeatService(
            $this->seatRepositoryMock,
            $this->officeRepositoryMock,
            $this->userRepositoryMock
        );
    }

    public function testCheckOfficeExistsReturnValue()
    {
        $office_id = 2;
        $this->officeRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(["id" => 2]);

        $result = $this->seatService->checkOfficeExists("id", $office_id);

        $this->assertEquals($result['id'], $office_id);
    }

    public function testCheckOfficeExistsThrowError()
    {
        $office_id = 2;
        $this->officeRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(null);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(OfficeResponse::NOT_FOUND->value);

        $this->seatService->checkOfficeExists("id", $office_id);
    }

    public function testCheckUserExistsReturnValue()
    {
        $user_id = 2;
        $this->userRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(["id" => 2]);

        $result = $this->seatService->checkUserExists("id", $user_id);

        $this->assertEquals($result['id'], $user_id);
    }

    public function testCheckUserExistsThrowError()
    {
        $user_id = 2;
        $this->userRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(null);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(UserResponse::NOT_FOUND->value);

        $this->seatService->checkUserExists("id", $user_id);
    }

    public function testCreateSeatSuccess()
    {
        $create_seat_dto = CreateSeatDto::fromArray(["label" => "A0", "position" => 10, "office_id" => 1]);
        $this->seatService = $this->createPartialMock(SeatService::class, [
            'checkOfficeExists',
        ]);
        $this->seatService->__construct(
            $this->seatRepositoryMock,
            $this->officeRepositoryMock,
            $this->userRepositoryMock
        );
        $this->seatService->expects($this->once())
            ->method("checkOfficeExists")
            ->willReturn(["id" => 1]);
        $this->seatRepositoryMock->expects($this->once())
            ->method("findByOfficeId")
            ->willReturn(false);
        $this->seatRepositoryMock->expects($this->once())
            ->method("create")
            ->willReturn(true);

        $result = $this->seatService->create($create_seat_dto);

        $this->assertTrue($result);
    }
}
