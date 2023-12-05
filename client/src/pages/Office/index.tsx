import MainLayout from "../../components/Layout/MainLayout";
import Seatmap from "../../components/Office/Seatmap";
import Sidebar from "../../components/Office/Sidebar";
import useCheckAdmin from "../../hooks/useCheckAdmin";

const Office = () => {
  const isAdmin = useCheckAdmin();

  return (
    <MainLayout>
      <div className="relative w-full pt-10 flex-1">
        <Seatmap />
        {isAdmin && <Sidebar />}
      </div>
    </MainLayout>
  );
};

export default Office;
