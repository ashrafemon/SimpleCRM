import client from "../Client";

export const fetchLeads = async (params: string) => {
    const res = await client.get(`/leads?${params}`);
    return res.data;
};

export const fetchLead = async (id: string) => {
    const res = await client.get(`/leads/${id}`);
    return res.data;
};

export const createLead = async (body: { [key: string]: string | any }) => {
    const res = await client.post(`/leads`, body);
    return res.data;
};

export const updateLead = async (
    id: string,
    body: { [key: string]: string | any }
) => {
    const res = await client.patch(`/leads/${id}`, body);
    return res.data;
};

export const deleteLead = async (id: string) => {
    const res = await client.delete(`/leads/${id}`);
    return res.data;
};
