import Divider from "../../Divider";
import Input from "../../Form/Input";

type Props = {
  title: string;
  onChange: (value: string) => void;
};

const OfficeTitleInput = ({ title = "Office 101", onChange }: Props) => {
  return (
    <div className="w-full flex flex-col items-center justify-center mb-10">
      <Divider className="bg-secondary" />
      <Divider className="bg-secondary" />
      <Input
        value={title}
        onChange={(e) => onChange(e.target.value)}
        type="text"
        className="text-5xl font-semibold text-center my-8 text-secondary max-w-5xl"
      />
      <Divider className="bg-secondary" />
      <Divider className="bg-secondary" />
    </div>
  );
};

export default OfficeTitleInput;
