import Divider from "../../Divider";

type Props = {
  title: string;
};

const OfficeTitle = ({ title = "Office 101" }: Props) => {
  return (
    <div className="w-full flex flex-col items-center justify-center mb-10">
      <Divider className="bg-secondary" />
      <Divider className="bg-secondary" />
      <h1 className="text-5xl font-semibold text-center my-8 text-secondary truncate max-w-5xl">
        {title}
      </h1>
      <Divider className="bg-secondary" />
      <Divider className="bg-secondary" />
    </div>
  );
};

export default OfficeTitle;
