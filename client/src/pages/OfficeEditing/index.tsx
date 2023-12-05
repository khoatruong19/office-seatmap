import MainLayout from "../../components/Layout/MainLayout";
import Seatmap from "../../components/OfficeEditing/Seatmap";

const OfficeEditing = () => {
  return (
    <MainLayout>
      <div className="relative w-full pt-10 flex-1">
        <Seatmap />
      </div>
    </MainLayout>
  );
};

export default OfficeEditing;
