import client from "../Client";

export const fetchLeadAssignments = async (params: string) => {
    const res = await client.get(`/lead-maintainers?${params}`);
    return res.data;
};

export const fetchLeadAssignment = async (id: string) => {
    const res = await client.get(`/lead-maintainers/${id}`);
    return res.data;
};

export const createLeadAssignment = async (body: {
    [key: string]: string | any;
}) => {
    const res = await client.post(`/lead-maintainers`, body);
    return res.data;
};

export const updateLeadAssignment = async (
    id: string,
    body: { [key: string]: string | any }
) => {
    const res = await client.patch(`/lead-maintainers/${id}`, body);
    return res.data;
};

export const deleteLeadAssignment = async (id: string) => {
    const res = await client.delete(`/lead-maintainers/${id}`);
    return res.data;
};
