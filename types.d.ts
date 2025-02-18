declare module "Validator";

interface ImportMeta {
    glob(pattern: string, options?: { eager?: boolean }): Record<string, any>;
}
