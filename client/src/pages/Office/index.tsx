import MainLayout from "../../components/Layout/MainLayout";
import Loader from "../../components/Loader";
import Seatmap from "../../components/Office/Seatmap";
import useGetOfficeInfo from "../../hooks/useGetOfficeInfo";

const Office = () => {
  const {
    isLoading,
    officeName,
    initBlocks: blocks,
    initSeats: seats,
    success,
  } = useGetOfficeInfo();

  if (isLoading) return <Loader />;

  return (
    <MainLayout>
      {success && (
        <Seatmap officeName={officeName} blocks={blocks} seats={seats} />
      )}
      {!isLoading && !success && (
        <div className="mx-auto w-fit mt-10">
          <h1 className="text-4xl font-semibold text-secondary">
            Office Not Found!
          </h1>
        </div>
      )}
    </MainLayout>
  );
};

export default Office;
