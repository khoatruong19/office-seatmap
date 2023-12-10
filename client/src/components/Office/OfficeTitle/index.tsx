import Divider from "@components/Divider";

type Props = {
  title: string;
};

const OfficeTitle = ({ title = "Office 101" }: Props) => {
  return (
    <div className="w-full flex flex-col items-center justify-center mb-10">
      <Divider className="bg-tertiary h-[3px]" />
      <Divider className="bg-tertiary h-[3px]" />
      <h1 className="text-5xl font-semibold text-center my-8 text-secondary truncate max-w-5xl">
        {title}
      </h1>
      <Divider className="bg-tertiary h-[3px]" />
      <Divider className="bg-tertiary h-[3px]" />
    </div>
  );
};

export default OfficeTitle;
