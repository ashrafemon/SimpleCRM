import client from "../Client";

export const login = async (body: { [key: string]: string | any }) => {
    const res = await client.post(`/auth/login`, body);
    return res.data;
};

export const logout = async () => {
    const res = await client.post(`/auth/logout`);
    return res.data;
};
