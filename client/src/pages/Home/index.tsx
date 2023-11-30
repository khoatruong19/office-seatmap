import Header from "../../components/Home/Header";
import Seatmap from "../../components/Home/Seatmap";

type Props = {};

const Home = (props: Props) => {
  return (
    <div className="min-h-screen bg-primary font-mono">
      <Header />

      <div className="mt-10 max-w-4xl w-full mx-auto">
        <Seatmap />
      </div>
    </div>
  );
};

export default Home;
