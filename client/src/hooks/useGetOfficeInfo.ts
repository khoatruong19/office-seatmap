import { useParams } from "react-router";
import { useGetOfficeQuery } from "../stores/office/service";
import { useMemo } from "react";
import { CellType } from "../schema/types";

const useGetOfficeInfo = () => {
  const { id } = useParams();
  const { data, isLoading } = useGetOfficeQuery(id!);

  const initBlocks = useMemo(() => {
    if (!data?.data) return [];
    let blocks = [];
    try {
      blocks = JSON.parse(data.data.blocks);
    } catch (error) {}
    return blocks;
  }, [data]);

  const initSeats: CellType[] = useMemo(() => {
    if (!data?.data) return [];
    return data?.data.seats
      ? data?.data.seats.map((seat) => ({
          label: seat.label,
          position: seat.position,
        }))
      : [];
  }, [data]);

  return {
    isLoading,
    officeId: data?.data.id ?? 0,
    officeName: data?.data.name ?? "Untitled",
    visible: !!data?.data.visible,
    initBlocks,
    initSeats,
    success: !!data?.data,
  };
};

export default useGetOfficeInfo;
