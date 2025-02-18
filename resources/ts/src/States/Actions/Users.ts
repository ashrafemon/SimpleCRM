import client from "../Client";

export const fetchUsers = async (params: string) => {
    const res = await client.get(`/users?${params}`);
    return res.data;
};

export const fetchUser = async (id: string) => {
    const res = await client.get(`/users/${id}`);
    return res.data;
};

export const createUser = async (body: { [key: string]: string | any }) => {
    const res = await client.post(`/users`, body);
    return res.data;
};

export const updateUser = async (
    id: string,
    body: { [key: string]: string | any }
) => {
    const res = await client.patch(`/users/${id}`, body);
    return res.data;
};

export const deleteUser = async (id: string) => {
    const res = await client.delete(`/users/${id}`);
    return res.data;
};
