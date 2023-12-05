import AddOfficeButton from "../../components/Home/AddOfficeButton";
import OfficeCard from "../../components/Home/OfficeCard";
import MainLayout from "../../components/Layout/MainLayout";
import OfficeTitle from "../../components/Office/OfficeTitle";

const offices = [
  {
    name: "Office 101",
    slug: "office-101",
  },
  {
    name: "Office 102",
    slug: "office-102",
  },
];

const Home = () => {
  return (
    <MainLayout>
      <div className="max-w-5xl w-full mx-auto mt-10">
        <OfficeTitle title="Offices" />

        <AddOfficeButton />

        <nav className="flex flex-col gap-4 w-full">
          {offices.map((office) => (
            <OfficeCard key={office.slug} office={office} />
          ))}
        </nav>
      </div>
    </MainLayout>
  );
};

export default Home;
