<?php

namespace __test__;

use core\HttpStatus;
use modules\cloudinary\CloudinaryService;
use modules\user\dto\CreateUserDto;
use modules\user\dto\UpdateProfileDto;
use modules\user\dto\UpdateUserDto;
use modules\user\UserRepository;
use modules\user\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private MockObject $cloudinaryServiceMock;
    private MockObject $userRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->cloudinaryServiceMock = $this->createMock(CloudinaryService::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepositoryMock, $this->cloudinaryServiceMock);
    }

    public function testCheckUserExistsReturnValue()
    {
        $user_id = 2;
        $this->userRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(["id" => 2]);

        $result = $this->userService->checkUserExists(strval($user_id));

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

        $this->userService->checkUserExists(strval($user_id));
    }

    public function testCheckEmailExistsNotReturnValue()
    {
        $email = "sdjksdh@gmail.com";
        $this->userRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(null);

        $result = $this->userService->checkEmailExists($email);

        $this->assertEmpty($result);
    }

    public function testCheckEmailExistsThrowError()
    {
        $email = "sdjksdh@gmail.com";
        $this->userRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn(["email" => $email]);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(UserResponse::EMAIL_EXISTS->value);

        $this->userService->checkEmailExists($email);
    }

    public function testCreateSuccess()
    {
        $this->userRepositoryMock->expects($this->once())
            ->method("create")
            ->willReturn("2");
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkEmailExists',
            'uploadAndSetAvatarUrl'
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkEmailExists");
        $this->userService->expects($this->once())->method("uploadAndSetAvatarUrl");
        $create_user_dto = CreateUserDto::fromArray(
            ["email" => "email", "full_name" => "full_name", "password" => "password", "role" => "admin"]
        );

        $result = $this->userService->create($create_user_dto);

        $this->assertEquals(["id" => 2], $result);
    }

    public function testFindOneSuccess()
    {
        $this->userRepositoryMock->expects($this->once())
            ->method("findOne")
            ->willReturn([
                "id" => 34,
                "email" => "test@gmail.com",
                "password" => "\$2y\$10\$nZ72rPoi8f0Jrj.uPTfxMOVydjwJ9XjaFrJugFOTp85MN.3VFceOu",
                "full_name" => "jdshfjk",
                "role" => "dsfjksdjf",
                "avatar" => "sdjkfgsjdkf",
                "created_at" => "sdfjlsdf",
                "updated_at" => "ksdklfhds"
            ]);

        $result = $this->userService->findOne("id", "2");

        $this->assertCount(8, $result);
    }

    public function testFindAllSuccess()
    {
        $this->userRepositoryMock->expects($this->once())
            ->method("findAll")
            ->willReturn([
                [
                    "id" => 34,
                    "email" => "test@gmail.com",
                    "password" => "\$2y\$10\$nZ72rPoi8f0Jrj.uPTfxMOVydjwJ9XjaFrJugFOTp85MN.3VFceOu",
                    "full_name" => "jdshfjk",
                    "role" => "dsfjksdjf",
                    "avatar" => "sdjkfgsjdkf",
                    "created_at" => "sdfjlsdf",
                    "updated_at" => "ksdklfhds"
                ],
                [
                    "id" => 35,
                    "email" => "test@gmail.com",
                    "password" => "\$2y\$10\$nZ72rPoi8f0Jrj.uPTfxMOVydjwJ9XjaFrJugFOTp85MN.3VFceOu",
                    "full_name" => "jdshfjk",
                    "role" => "dsfjksdjf",
                    "avatar" => "sdjkfgsjdkf",
                    "created_at" => "sdfjlsdf",
                    "updated_at" => "ksdklfhds"
                ]
            ]);

        $result = $this->userService->findAll();

        $this->assertCount(2, $result);
    }

    public function testGetCurrentUserSuccess()
    {
        $user_id = 2;
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")->willReturn([
            "id" => 2,
            "email" => "test@gmail.com",
            "password" => "\$2y\$10\$nZ72rPoi8f0Jrj.uPTfxMOVydjwJ9XjaFrJugFOTp85MN.3VFceOu",
            "full_name" => "jdshfjk",
            "role" => "dsfjksdjf",
            "avatar" => "sdjkfgsjdkf",
            "created_at" => "sdfjlsdf",
            "updated_at" => "ksdklfhds"
        ]);

        $result = $this->userService->getCurrentUser(strval($user_id));

        $this->assertCount(1, $result);
        $this->assertArrayHasKey("user", $result);
        $this->assertEquals(2, $result['user']['id']);
    }

    public function testGetCurrentUserFail()
    {
        $user_id = 2;
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willThrowException(new ResponseException(HttpStatus::$BAD_REQUEST, UserResponse::NOT_FOUND->value));

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(UserResponse::NOT_FOUND->value);

        $this->userService->getCurrentUser(strval($user_id));
    }

    public function testUpdateOneSuccess()
    {
        $user_id = "2";
        $update_user_dto = UpdateUserDto::fromArray(
            ["email" => "test@gmail.com", "full_name" => "fullname", "role" => "user"]
        );
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
            'uploadAndSetAvatarUrl'
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(
                [
                    "id" => 2,
                    "email" => "test2@gmail.com",
                    "full_name" => "fullname",
                    "password" => "passwordsd",
                    "role" => "user"
                ]
            );
        $this->userRepositoryMock->expects($this->once())
            ->method("updateOne")
            ->willReturn(true);

        $result = $this->userService->updateOne($user_id, $update_user_dto);

        $this->assertArrayHasKey("email", $result);
        $this->assertArrayHasKey("full_name", $result);
        $this->assertArrayHasKey("role", $result);
        $this->assertArrayHasKey("avatar", $result);
        $this->assertArrayNotHasKey("password", $result);
    }

    public function testUpdateOneFail()
    {
        $user_id = "2";
        $update_user_dto = UpdateUserDto::fromArray(
            ["email" => "test@gmail.com", "full_name" => "fullname", "role" => "user"]
        );
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
            'uploadAndSetAvatarUrl'
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(
                [
                    "id" => 2,
                    "email" => "test2@gmail.com",
                    "full_name" => "fullname",
                    "password" => "passwordsd",
                    "role" => "user"
                ]
            );
        $this->userRepositoryMock->expects($this->once())
            ->method("updateOne")
            ->willReturn(false);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(UserResponse::UPDATE_USER_FAIL->value);

        $this->userService->updateOne($user_id, $update_user_dto);
    }

    public function testUpdateProfileSuccess()
    {
        $user_id = "2";
        $update_profile_dto = UpdateProfileDto::fromArray(["full_name" => "fullname"]);
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(
                [
                    "id" => 2,
                    "email" => "test2@gmail.com",
                    "full_name" => "fullname",
                    "password" => "passwordsd",
                    "role" => "user"
                ]
            );
        $this->userRepositoryMock->expects($this->once())
            ->method("updateOne")
            ->willReturn(true);

        $result = $this->userService->updateProfile($user_id, $update_profile_dto);

        $this->assertArrayHasKey("email", $result);
        $this->assertArrayHasKey("full_name", $result);
        $this->assertArrayHasKey("role", $result);
        $this->assertArrayHasKey("avatar", $result);
        $this->assertArrayNotHasKey("password", $result);
    }

    public function testUpdateProfileFail()
    {
        $user_id = "2";
        $update_profile_dto = UpdateProfileDto::fromArray(["full_name" => "fullname"]);
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(
                [
                    "id" => 2,
                    "email" => "test2@gmail.com",
                    "full_name" => "fullname",
                    "password" => "passwordsd",
                    "role" => "user"
                ]
            );
        $this->userRepositoryMock->expects($this->once())
            ->method("updateOne")
            ->willReturn(false);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(UserResponse::UPDATE_PROFILE_FAIL->value);

        $this->userService->updateProfile($user_id, $update_profile_dto);
    }

    public function testDeleteWithAvatarSuccess()
    {
        $user_id = "2";
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(
                [
                    "id" => 2,
                    "email" => "test2@gmail.com",
                    "full_name" => "fullname",
                    "password" => "passwordsd",
                    "role" => "user",
                    "avatar" => "sdsdj"
                ]
            );
        $this->userRepositoryMock->expects($this->once())
            ->method("delete")
            ->willReturn(true);

        $this->cloudinaryServiceMock->expects($this->once())->method("deleteFile");

        $this->userService->delete($user_id);
    }

    public function testDeleteWithNoAvatarSuccess()
    {
        $user_id = "2";
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(
                [
                    "id" => 2,
                    "email" => "test2@gmail.com",
                    "full_name" => "fullname",
                    "password" => "passwordsd",
                    "role" => "user",
                    "avatar" => null
                ]
            );
        $this->userRepositoryMock->expects($this->once())
            ->method("delete")
            ->willReturn(true);

        $this->cloudinaryServiceMock->expects($this->never())->method("deleteFile");

        $this->userService->delete($user_id);
    }

    public function testDeleteFail()
    {
        $user_id = "2";
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(
                [
                    "id" => 2,
                    "email" => "test2@gmail.com",
                    "full_name" => "fullname",
                    "password" => "passwordsd",
                    "role" => "user",
                    "avatar" => null
                ]
            );
        $this->userRepositoryMock->expects($this->once())
            ->method("delete")
            ->willReturn(false);

        $this->cloudinaryServiceMock->expects($this->never())->method("deleteFile");

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(UserResponse::DELETE_USER_FAIL->value);

        $this->userService->delete($user_id);
    }

    public function testUploadAvatarSuccess()
    {
        $user_id = "2";
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
            'uploadAndGetFileUrl',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("uploadAndGetFileUrl")
            ->willReturn("avatar-url");
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(["id" => 2, "email" => "test2@gmail.com", "full_name" => "fullname"]);
        $this->userRepositoryMock->expects($this->once())
            ->method("updateOne")
            ->willReturn(true);

        $url = $this->userService->uploadAvatar($user_id);

        $this->assertEquals($url, "avatar-url");
    }

    public function testUploadAvatarFail()
    {
        $user_id = "2";
        $this->userService = $this->createPartialMock(UserService::class, [
            'checkUserExists',
            'uploadAndGetFileUrl',
        ]);
        $this->userService->__construct($this->userRepositoryMock, $this->cloudinaryServiceMock);
        $this->userService->expects($this->once())->method("uploadAndGetFileUrl")
            ->willReturn(null);
        $this->userService->expects($this->once())->method("checkUserExists")
            ->willReturn(["id" => 2, "email" => "test2@gmail.com", "full_name" => "fullname"]);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(UserResponse::NO_FILE_FOUND->value);

        $this->userService->uploadAvatar($user_id);
    }
}
