import MainLayout from "../../components/Layout/MainLayout";
import Loader from "../../components/Loader";
import Seatmap from "../../components/OfficeEditing/Seatmap";
import useGetOfficeInfo from "../../hooks/useGetOfficeInfo";

const OfficeEditing = () => {
  const {
    isLoading,
    officeId,
    officeName,
    visible,
    initBlocks,
    initSeats,
    success,
  } = useGetOfficeInfo();

  if (isLoading) return <Loader />;

  return (
    <MainLayout>
      {success && (
        <Seatmap
          officeName={officeName}
          officeId={officeId}
          initBlocks={initBlocks}
          initSeats={initSeats}
          isVisible={visible}
        />
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

export default OfficeEditing;
