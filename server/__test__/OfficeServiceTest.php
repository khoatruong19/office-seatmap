<?php

namespace __test__;

use modules\office\dto\CreateOfficeDto;
use modules\office\dto\UpdateOfficeDto;
use modules\office\OfficeRepository;
use modules\office\OfficeService;
use modules\seat\SeatService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use shared\enums\OfficeResponse;
use shared\exceptions\ResponseException;

class OfficeServiceTest extends TestCase
{
    private OfficeService $officeService;
    private MockObject $officeRepositoryMock;
    private MockObject $seatServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->officeRepositoryMock = $this->createMock(OfficeRepository::class);
        $this->seatServiceMock = $this->createMock(SeatService::class);
        $this->officeService = new OfficeService($this->officeRepositoryMock, $this->seatServiceMock);
    }

    public function testCheckOfficeExistsReturnValue()
    {
        $office_id = 2;
        $this->officeRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(["id" => 2]);

        $result = $this->officeService->checkOfficeExists("id", $office_id);

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

        $this->officeService->checkOfficeExists("id", $office_id);
    }

    public function testCheckOfficeNotExistsThrowError()
    {
        $office_name = "Office 101";
        $this->officeRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(["id" => 2]);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(OfficeResponse::OFFICE_NAME_EXISTS->value);

        $this->officeService->checkOfficeNotExists($office_name);
    }

    public function testCreateOfficeSuccess()
    {
        $create_office_dto = CreateOfficeDto::fromArray(["name" => "Office 101"]);
        $this->officeService = $this->createPartialMock(OfficeService::class, [
            'checkOfficeNotExists',
        ]);
        $this->officeService->__construct($this->officeRepositoryMock, $this->seatServiceMock);
        $this->officeService->expects($this->once())
            ->method("checkOfficeNotExists")
            ->willReturn(true);
        $this->officeRepositoryMock->expects($this->once())
            ->method("create")
            ->willReturn("1");

        $result = $this->officeService->create($create_office_dto);

        $this->assertEquals(1, $result);
    }

    public function testUpdateOfficeSuccess()
    {
        $update_office_dto = UpdateOfficeDto::fromArray(
            [
                "id" => 1,
                "name" => "Office 101 updated",
                "visible" => true,
                "blocks" => "[]",
                "seats" => [["label" => "AO", "position" => 10, "office_id" => 1]],
                "delete_seats" => []
            ]
        );
        $this->officeService = $this->createPartialMock(OfficeService::class, [
            'checkOfficeExists',
        ]);
        $this->officeService->__construct($this->officeRepositoryMock, $this->seatServiceMock);
        $this->officeService->expects($this->once())
            ->method("checkOfficeExists")
            ->willReturn(
                [
                    "id" => 1,
                    "name" => "Office 101",
                    "visible" => true,
                    "blocks" => "[]",
                ]
            );
        $this->officeRepositoryMock->expects($this->once())
            ->method("updateOne")
            ->willReturn(true);
        $this->seatServiceMock->expects($this->once())->method("create")->willReturn(true);
        $this->seatServiceMock->expects($this->never())->method("deleteSeatByLabel");

        $result = $this->officeService->update($update_office_dto);

        $this->assertTrue($result);
    }

    public function testFindOneSuccess()
    {
        $office_id = "2";
        $this->officeService = $this->createPartialMock(OfficeService::class, [
            'checkOfficeExists',
        ]);
        $this->officeService->__construct($this->officeRepositoryMock, $this->seatServiceMock);
        $this->officeService->expects($this->once())
            ->method("checkOfficeExists")
            ->willReturn(
                [
                    "id" => 1,
                    "name" => "Office 101",
                    "visible" => true,
                    "blocks" => "[]",
                ]
            );
        $this->seatServiceMock->expects($this->once())->method("findAllByOfficeId")->willReturn(
            [
                ["id" => 1, "label" => "AO", "position" => 10, "office_id" => 1],
                ["id" => 2, "label" => "A1", "position" => 11, "office_id" => 1]
            ]
        );

        $result = $this->officeService->findOne("id", $office_id);

        $this->assertArrayHasKey("id", $result);
        $this->assertArrayHasKey("name", $result);
        $this->assertArrayHasKey("visible", $result);
        $this->assertArrayHasKey("blocks", $result);
        $this->assertArrayHasKey("seats", $result);
        $this->assertCount(2, $result['seats']);
    }

    public function testDeleteSuccess()
    {
        $office_id = "2";
        $this->officeService = $this->createPartialMock(OfficeService::class, [
            'checkOfficeExists',
        ]);
        $this->officeService->__construct($this->officeRepositoryMock, $this->seatServiceMock);
        $this->officeService->expects($this->once())
            ->method("checkOfficeExists")
            ->willReturn(
                [
                    "id" => 1,
                    "name" => "Office 101",
                    "visible" => true,
                    "blocks" => "[]",
                ]
            );
        $this->officeRepositoryMock->expects($this->once())
            ->method("delete")
            ->willReturn(true);

        $result = $this->officeService->delete($office_id);

        $this->assertTrue($result);
    }

    public function testDeleteFail()
    {
        $office_id = "2";
        $this->officeService = $this->createPartialMock(OfficeService::class, [
            'checkOfficeExists',
        ]);
        $this->officeService->__construct($this->officeRepositoryMock, $this->seatServiceMock);
        $this->officeService->expects($this->once())
            ->method("checkOfficeExists")
            ->willReturn(
                [
                    "id" => 1,
                    "name" => "Office 101",
                    "visible" => true,
                    "blocks" => "[]",
                ]
            );
        $this->officeRepositoryMock->expects($this->once())
            ->method("delete")
            ->willReturn(false);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(OfficeResponse::DELETE_OFFICE_FAIL->value);

        $this->officeService->delete($office_id);
    }

}
