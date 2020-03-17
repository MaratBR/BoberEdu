<template>
    <div class="loader" :class="{loading: !completed}">
        <div class="sk-chase" v-if="!completed">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>
        <div v-else class="loader__wrapper">
            <template v-if="promiseMode">
                <slot v-if="success && value" :value="value"></slot>
                <error v-else-if="error" :error="error" />
                <error v-else :error="noValueMessage" />
            </template>
            <slot v-else></slot>
        </div>
    </div>
</template>

<script lang="ts">
    import {PropValidator} from "vue/types/options";
    import Error from "./Error.vue";

    export default {
        name: "Loader",
        components: {Error},
        props: {
            promise: {
                type: Promise
            } as PropValidator<Promise<any>>,
            noValueMessage: {
                type: String,
                default: 'No data were retrieved'
            },
            loading: {
                type: Boolean,
                default: false
            },
            promiseMode: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                value: null,
                completed: false,
                success: false,
                error: null
            }
        },
        methods: {
            updateFrom(promise: Promise<any>) {
                if (!promise)
                    return;
                this.completed = false;
                this.success = null;
                this.error = null;
                if (promise && !this.$slots.default)
                    promise
                        .then(v => {
                            this.value = v;
                            this.success = true
                        })
                        .catch(err => {
                            this.error = err || 'Unknown error'
                            this.success = false
                        })
                        .finally(() => this.completed = true)
            }
        },
        created(): void {
            if (this.promiseMode && this.promise)
                this.updateFrom(this.promise);
        },
        watch: {
            promise(newPromise) {
                if (this.promiseMode)
                    this.updateFrom(newPromise)
            },
            loading(newValue) {
                if (this.promiseMode)
                    return;

                this.completed = !newValue;
            }
        }
    }
</script>

<style scoped>
    .loading {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sk-chase {
        width: 90px;
        height: 90px;
        animation: sk-chase 2.5s infinite linear both;
    }

    .sk-chase-dot {
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        animation: sk-chase-dot 2.0s infinite ease-in-out both;
    }

    .sk-chase-dot:before {
        content: '';
        display: block;
        width: 25%;
        height: 25%;
        background-color: #000;
        border-radius: 100%;
        animation: sk-chase-dot-before 2.0s infinite ease-in-out both;
    }

    .sk-chase-dot:nth-child(1) { animation-delay: -1.1s; }
    .sk-chase-dot:nth-child(2) { animation-delay: -1.0s; }
    .sk-chase-dot:nth-child(3) { animation-delay: -0.9s; }
    .sk-chase-dot:nth-child(4) { animation-delay: -0.8s; }
    .sk-chase-dot:nth-child(5) { animation-delay: -0.7s; }
    .sk-chase-dot:nth-child(6) { animation-delay: -0.6s; }
    .sk-chase-dot:nth-child(1):before { animation-delay: -1.1s; }
    .sk-chase-dot:nth-child(2):before { animation-delay: -1.0s; }
    .sk-chase-dot:nth-child(3):before { animation-delay: -0.9s; }
    .sk-chase-dot:nth-child(4):before { animation-delay: -0.8s; }
    .sk-chase-dot:nth-child(5):before { animation-delay: -0.7s; }
    .sk-chase-dot:nth-child(6):before { animation-delay: -0.6s; }

    @keyframes sk-chase {
        100% { transform: rotate(360deg); }
    }

    @keyframes sk-chase-dot {
        80%, 100% { transform: rotate(360deg); }
    }

    @keyframes sk-chase-dot-before {
        50% {
            transform: scale(0.4);
        }
        100%, 0% {
            transform: scale(1.0);
        }
    }
</style>
