import client from "../Client";

export const fetchApplications = async (params: string) => {
    const res = await client.get(`/applications?${params}`);
    return res.data;
};

export const fetchApplication = async (id: string) => {
    const res = await client.get(`/applications/${id}`);
    return res.data;
};

export const createApplication = async (body: {
    [key: string]: string | any;
}) => {
    const res = await client.post(`/applications`, body);
    return res.data;
};

export const updateApplication = async (
    id: string,
    body: { [key: string]: string | any }
) => {
    const res = await client.patch(`/applications/${id}`, body);
    return res.data;
};

export const deleteApplication = async (id: string) => {
    const res = await client.delete(`/applications/${id}`);
    return res.data;
};
