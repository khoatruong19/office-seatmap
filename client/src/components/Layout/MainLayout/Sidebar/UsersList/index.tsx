import ClipLoader from "react-spinners/ClipLoader";
import { useGetAllUsersQuery } from "../../../../../stores/user/service";
import UserCard from "./UserCard";

const UsersList = () => {
  const { data, isLoading } = useGetAllUsersQuery();

  if (isLoading)
    return (
      <div className="mx-auto mt-5">
        <ClipLoader />
      </div>
    );

  if (!data)
    return (
      <h3 className="text-center font-semibold text-2xl mt-10">
        No users found
      </h3>
    );

  return (
    <div className="mt-8 pl-1 pr-1.5">
      {data.data.map((user) => (
        <UserCard key={user.id} user={user} />
      ))}
    </div>
  );
};

export default UsersList;
