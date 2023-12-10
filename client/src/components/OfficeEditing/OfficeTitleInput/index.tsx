import Divider from "@components/Divider";
import Input from "@components/Form/Input";

type Props = {
  title: string;
  onChange: (value: string) => void;
};

const OfficeTitleInput = ({ title = "Office 101", onChange }: Props) => {
  return (
    <div className="w-full flex flex-col items-center justify-center mb-10">
      <Divider className="bg-tertiary h-[3px]" />
      <Divider className="bg-tertiary h-[3px]" />
      <Input
        value={title}
        onChange={(e) => onChange(e.target.value)}
        type="text"
        className="text-5xl font-semibold text-center my-6 text-secondary max-w-5xl"
      />
      <Divider className="bg-tertiary h-[3px]" />
      <Divider className="bg-tertiary h-[3px]" />
    </div>
  );
};

export default OfficeTitleInput;
