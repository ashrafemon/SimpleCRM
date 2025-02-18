import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";
import vueDevTools from "vite-plugin-vue-devtools";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/ts/app.ts"],
            refresh: true,
        }),
        vue(),
        vueDevTools(),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/ts/src"),
        },
    },
    build: {
        chunkSizeWarningLimit: 200 * 1024,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes("node_modules")) {
                        return id
                            .toString()
                            .split("node_modules/")[1]
                            .split("/")[0]
                            .toString();
                    }
                },
                assetFileNames: (assetInfo) => {
                    const fileName = assetInfo.name || "";
                    const extType = fileName.split(".").pop();

                    if (
                        extType &&
                        /png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)
                    ) {
                        return `static/img/[name]-[hash][extname]`;
                    } else if (extType && /woff|woff2/.test(extType)) {
                        return `static/css/[name]-[hash][extname]`;
                    } else {
                        return `static/other/[name]-[hash][extname]`;
                    }
                },
                chunkFileNames: "static/js/[name]-[hash].js",
                entryFileNames: "static/js/[name]-[hash].js",
            },
        },
    },
    server: {
        port: 3000,
    },
});
