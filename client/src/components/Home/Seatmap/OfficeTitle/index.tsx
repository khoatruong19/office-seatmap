import Divider from "../../../Divider";

type Props = {
  title: string;
};

const OfficeTitle = ({ title }: Props) => {
  return (
    <div className="w-full flex flex-col items-center justify-center mb-10">
      <Divider />
      <Divider />
      <h1 className="uppercase text-5xl font-semibold text-red-500 my-5">
        {title}
      </h1>
      <Divider />
      <Divider />
    </div>
  );
};

export default OfficeTitle;
