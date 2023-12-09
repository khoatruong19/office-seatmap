import { useParams } from "react-router";
import { useGetOfficeQuery } from "@stores/office/service";
import { useMemo } from "react";
import { CellType } from "@schema/types";

const useGetOfficeInfo = () => {
  const { id } = useParams();
  const { data: response, isLoading } = useGetOfficeQuery(id!);

  const blocks = useMemo(() => {
    if (!response?.data) return [];
    let blocks = [];
    try {
      blocks = JSON.parse(response.data.blocks);
    } catch (error) {
      return;
    }
    return blocks;
  }, [response]);

  const cells: CellType[] = useMemo(() => {
    if (!response?.data) return [];

    return response?.data.seats
      ? response?.data.seats.map((seat) => ({
          label: seat.label,
          position: seat.position,
        }))
      : [];
  }, [response]);

  return {
    isLoading,
    officeId: response?.data.id ?? 0,
    officeName: response?.data.name ?? "Untitled",
    visible: !!response?.data.visible,
    blocks,
    cells,
    seats: response?.data.seats ?? [],
    success: !!response?.data,
  };
};

export default useGetOfficeInfo;
