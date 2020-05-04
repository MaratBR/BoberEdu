export interface IUploader<T = void> {
    upload(file: File, progressCallback: (value: number) => any): Promise<T>;
}
