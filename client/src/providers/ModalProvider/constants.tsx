import { ReactElement } from "react";
import ProfileModal from "../../components/Modals/ProfileModal";
import UserEditingModal from "../../components/Modals/UserEditingModal";
import UserInformationModal from "../../components/Modals/UserInformationModal";
import ConfirmModal from "../../components/Modals/ConfirmModal";
import AddOfficeModal from "../../components/Modals/AddOfficeModal";

export enum MODALS {
  PROFILE = "profile",
  CREATE_USER = "create-user",
  UPDATE_USER = "update-user",
  USER_INFORMATION = "user-information",
  CONFIRM = "confirm",
  ADD_OFFICE = "add-office",
}

export const MODAL_ELEMENTS: Record<MODALS, ReactElement> = {
  [MODALS.PROFILE]: <ProfileModal />,
  [MODALS.CREATE_USER]: <UserEditingModal type="create" />,
  [MODALS.UPDATE_USER]: <UserEditingModal type="update" />,
  [MODALS.USER_INFORMATION]: <UserInformationModal />,
  [MODALS.CONFIRM]: <ConfirmModal confirmHandler={() => {}} />,
  [MODALS.ADD_OFFICE]: <AddOfficeModal />,
};
