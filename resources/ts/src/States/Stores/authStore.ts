import { defineStore } from "pinia";
import { ref } from "vue";

interface User {
    id?: string;
    name?: string;
    email?: string;
    role?: string;
    [key: string]: any;
}

interface AuthState {
    isAuthenticated: boolean;
    currentUser: User | null;
    token: string | null;
}

const useAuthStore = defineStore("auth", () => {
    const isAuthenticated = ref(false);
    const currentUser = ref<User | null>(null);
    const token = ref<string | null>(null);

    const setAuth = (payload: {
        isAuthenticated: boolean;
        currentUser: User;
        token: string;
    }) => {
        isAuthenticated.value = payload.isAuthenticated;
        currentUser.value = payload.currentUser;
        token.value = payload.token;
    };

    const setToken = (payload: string | null) => {
        token.value = payload;
    };

    const resetAuth = () => {
        isAuthenticated.value = false;
        currentUser.value = null;
        token.value = null;
    };

    return {
        isAuthenticated,
        currentUser,
        token,
        setAuth,
        setToken,
        resetAuth,
    };
});

// {
//     state: (): AuthState => ({
//         isAuthenticated: false,
//         currentUser: null,
//         token: null,
//     }),

//     actions: {
//         setAuth(payload: {
//             isAuthenticated: boolean;
//             currentUser: User;
//             token: string;
//         }) {
//             this.isAuthenticated = payload.isAuthenticated;
//             this.currentUser = payload.currentUser;
//             this.token = payload.token;
//         },

//         setToken(token: string | null) {
//             this.token = token;
//         },

//         resetAuth() {
//             this.isAuthenticated = false;
//             this.currentUser = null;
//             this.token = null;
//         },
//     },

//     persist: {
//         key: "auth",
//         storage: localStorage,
//         paths: ["isAuthenticated", "currentUser", "token"],
//     },
// }

export default useAuthStore;
