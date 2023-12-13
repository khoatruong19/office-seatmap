import { useAuth } from "@/hooks/useAuth";
import AddOfficeButton from "@components/Home/AddOfficeButton";
import OfficeCard from "@components/Home/OfficeCard";
import MainLayout from "@components/Layout/MainLayout";
import OfficeTitle from "@components/Office/OfficeTitle";
import { useGetAllOfficesQuery } from "@stores/office/service";

const Home = () => {
  const { user } = useAuth();
  if (!user) return null;

  const { data, isLoading } = useGetAllOfficesQuery();
  const offices = (data && data?.data) ?? [];

  return (
    <MainLayout>
      <div className="max-w-5xl w-full mx-auto mt-10">
        <OfficeTitle title="Offices" />

        <AddOfficeButton />

        <nav className="flex flex-col gap-4 w-full">
          {offices.map((office) => (
            <OfficeCard key={office.id} office={office} />
          ))}
        </nav>
        {!isLoading && !offices.length && (
          <h2 className="text-center text-2xl font-semibold text-secondary">
            No offices found!
          </h2>
        )}
      </div>
    </MainLayout>
  );
};

export default Home;
