import { ReactElement } from "react";
import ProfileModal from "@components/Modals/ProfileModal";
import UserEditingModal from "@components/Modals/UserEditingModal";
import UserInformationModal from "@components/Modals/UserInformationModal";
import ConfirmModal from "@components/Modals/ConfirmModal";
import AddOfficeModal from "@components/Modals/AddOfficeModal";
import AddBlockModal from "@components/Modals/AddBlockModal";
import { UserEditingModalType } from "@/schema/types";

export enum MODALS {
  PROFILE = "profile",
  CREATE_USER = "create-user",
  UPDATE_USER = "update-user",
  USER_INFORMATION = "user-information",
  CONFIRM = "confirm",
  ADD_OFFICE = "add-office",
  ADD_BLOCK = "add-block",
}

export const MODAL_ELEMENTS: Record<MODALS, ReactElement> = {
  [MODALS.PROFILE]: <ProfileModal />,
  [MODALS.CREATE_USER]: <UserEditingModal type={UserEditingModalType.CREATE} />,
  [MODALS.UPDATE_USER]: <UserEditingModal type={UserEditingModalType.UPDATE} />,
  [MODALS.USER_INFORMATION]: <UserInformationModal />,
  [MODALS.CONFIRM]: <ConfirmModal confirmHandler={() => {}} />,
  [MODALS.ADD_OFFICE]: <AddOfficeModal confirmHandler={() => {}} />,
  [MODALS.ADD_BLOCK]: <AddBlockModal confirmHandler={() => {}} />,
};
