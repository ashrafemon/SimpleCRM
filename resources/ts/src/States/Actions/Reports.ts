import client from "../Client";

export const fetchSummaryReports = async () => {
    const res = await client.get(`/reports/summary`);
    return res.data;
};

export const fetchCounselorStatsReports = async () => {
    const res = await client.get(`/reports/stats`);
    return res.data;
};
