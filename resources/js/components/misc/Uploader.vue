<template>
    <div class="input input--upload" :aria-invalid="!!error">
        <div class="uploader">
            <div>
                <label :for="id">
                    {{ title }}
                    <span class="filesize" v-if="file"> | {{ size }}</span>
                </label>
                <input :accept="accept" type="file" :id="id" @change="onFileChanged" ref="input">
                <span class="input__error" v-if="error">{{ error }}</span>
            </div>

            <button class="btn" :disabled="!!error || uploading" @click="upload">
                <i class="fa fa-upload" v-if="!uploading"></i>
                <i class="fa fa-spin fa-spinner" v-else></i>
            </button>
        </div>
        <div class="upload-progress" v-if="uploading"></div>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";
    import {IUploader} from "../../store/upload";

    function sizeToString(s: number) {
        let parts: [number, string] = s >= 1048576 ? [s / 1048576, 'M'] :
            s > 1024 ? [s / 1024, 'K'] : [s, 'B'];
        parts[0] = Math.round(parts[0]);
        return parts[0]+parts[1];
    }

    @Component
    export default class Uploader extends Vue {
        filename: string = null;
        file: File = null;
        error: string = null;
        uploading: boolean = null;
        progress: number = 0;

        @Prop({ default: () => 'Uploader' + Math.round(Math.random()*10000) }) id: string;
        @Prop({ default: -1 }) max: number;
        @Prop({ default: -1 }) min: number;
        @Prop({ default: '*/*' }) accept: string;
        @Prop({ default: 'Upload file' }) defaultText: string;
        @Prop({ default: null }) uploader: IUploader<any>;
        @Prop({ default: true }) resetAfterUpload: boolean;

        get title() {
            return this.filename || this.defaultText
        }

        get size() {
            if (this.file) {
                return sizeToString(this.file.size)
            }
            return null;
        }

        onFileChanged(e) {
            if (e.target.files.length === 0)
                return;
            this.file = e.target.files[0];
            this.filename = this.file.name;
            this.$emit('fileChanged', this.file)
            this.validate()
        }

        async upload() {
            if (this.uploader !== null && this.file && !this.error) {
                this.uploading = true;
                this.progress = 0;
                this.$emit('uploading')


                let value = await this.uploader.upload(this.file, v => this.progress = v);
                this.$emit('uploaded', value)
                this.uploading = false

                if (this.resetAfterUpload) {
                    this.file = this.filename = null
                }
            }

        }

        validate() {
            this.error = null;

            if (this.max != -1) {
                if (this.max < this.file.size) {
                    this.error = 'File too big. ' + sizeToString(this.max) + '(' + this.max + ' bytes) at most'
                    return
                }
            }

            if (this.min != -1) {
                if (this.min > this.file.size) {
                    this.error = 'File too small. ' + sizeToString(this.min) + '(' + this.min + ' bytes) at least'
                    return
                }
            }

            this.$emit(this.error ? 'invalid' : 'valid')
        }
    }
</script>

<style scoped lang="scss">
    input {
        display: none;
    }

    .uploader {
        display: flex;
        align-items: center;

        & > button {
            margin-left: 6px;
            border: none;
            background: none;

            &:hover {
                background: #ccc;
            }
        }
    }
</style>
