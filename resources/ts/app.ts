import NaiveProvider from "@/Providers/NaiveProvider.vue";
import { createInertiaApp } from "@inertiajs/vue3";
import naive from "naive-ui";
import { createApp, h } from "vue";

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
                    default: () => h(App, props),
                }),
        })
            .use(plugin)
            .use(naive)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
