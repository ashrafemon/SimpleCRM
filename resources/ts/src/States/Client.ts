import { API_URL } from "@/Constants/urls";
import axios from "axios";
import Cookies from "js-cookie";

const client = axios.create({ baseURL: API_URL, timeout: 10000 });
client.defaults.headers.common["Accept"] = "application/json";
client.defaults.headers.common["Content-Type"] = "application/json";

client.interceptors.request.use(
    (config) => {
        const token = Cookies.get("token");
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

client.interceptors.response.use(
    (res) => res,
    (err) => Promise.reject(err.response)
);

export default client;
