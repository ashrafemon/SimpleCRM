import NaiveProvider from "@/Providers/NaiveProvider.vue";
import { createInertiaApp } from "@inertiajs/vue3";
import naive from "naive-ui";
import { createApp, h } from "vue";
import { VueQueryPlugin } from "@tanstack/vue-query";
import AuthProvider from "@/Providers/AuthProvider.vue";
import { KanbanPlugin } from "@syncfusion/ej2-vue-kanban";
import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./src/Pages/**/*.vue", { eager: true });
        return pages[`./src/Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({
            title: (title: string) => `${title} - SimpleCRM`,
            render: () =>
                h(NaiveProvider, null, {
                    default: () =>
                        h(AuthProvider, null, {
                            default: () => h(App, props),
                        }),
                    // h(App, props)
                }),
        })
            .use(plugin)
            .use(naive)
            .use(VueQueryPlugin)
            .use(KanbanPlugin)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
