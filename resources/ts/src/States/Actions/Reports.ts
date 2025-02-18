import client from "../Client";

export const fetchSummaryReports = async (params: string) => {
    const res = await client.get(`/reports/summary?${params}`);
    return res.data;
};
