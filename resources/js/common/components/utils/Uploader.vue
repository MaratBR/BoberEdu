<template>
    <div class="input input--upload" :aria-invalid="ariaInvalid">
        <div class="uploader">
            <div>
                <label :for="id">
                    {{ title }}
                    <span class="filesize" v-if="value"> | {{ size }}</span>
                </label>
                <input :accept="accept" type="file" :id="id" @change="onFileChanged" ref="input">
                <span class="input__error" v-if="error">{{ error }}</span>
            </div>

            <button class="btn" :disabled="!!error || uploading || value === null" @click.prevent="upload">
                <i class="fa fa-upload" v-if="!uploading"></i>
                <i class="fa fa-spin fa-spinner" v-else></i>
            </button>
        </div>
        <div class="upload-progress" v-if="uploading"></div>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";

    function sizeToString(s: number) {
        let parts: [number, string] = s >= 1048576 ? [s / 1048576, 'M'] :
            s > 1024 ? [s / 1024, 'K'] : [s, 'B'];
        parts[0] = Math.round(parts[0]);
        return parts[0]+parts[1];
    }

    @Component
    export default class Uploader extends Vue {
        filename: string = null;
        error: string = null;

        @Prop({ default: false }) uploading: boolean;
        @Prop({ default: null }) value: File;
        @Prop({ default: () => 'Uploader' + Math.round(Math.random()*10000) }) id: string;
        @Prop({ default: -1 }) max: number;
        @Prop({ default: -1 }) min: number;
        @Prop({ default: '*/*' }) accept: string;
        @Prop({ default: 'Upload file' }) defaultText: string;

        get title() {
            return this.value ? this.value.name : this.defaultText
        }

        get size() {
            if (this.value) {
                return sizeToString(this.value.size)
            }
            return null;
        }

        get ariaInvalid(): string {
            return this.error ? 'true' : 'false'
        }

        onFileChanged(e) {
            if (e.target.files.length === 0)
                return;
            let file = e.target.files[0];
            this.filename = file.name;
            this.$emit('input', file)
            this.validate()
        }

        async upload() {
            this.$emit('upload', this.value)
        }

        validate() {
            this.error = null;

            if (!this.value)
                return;

            if (this.max != -1) {
                if (this.max < this.value.size) {
                    this.error = 'File too big. ' + sizeToString(this.max) + '(' + this.max + ' bytes) at most'
                    return
                }
            }

            if (this.min != -1) {
                if (this.min > this.value.size) {
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
