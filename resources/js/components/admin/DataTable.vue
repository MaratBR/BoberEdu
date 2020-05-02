<template>
    <table class="data-table table">
        <tr>
            <th v-for="c in columnNames">
                {{ c }}
            </th>
        </tr>

        <tbody>
            <tr v-for="r in data">
                <td v-for="c in columnNames">
                    {{ r[c] }}
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script lang="ts">
    import {Component, Prop, Vue, Watch} from "vue-property-decorator";
    @Component
    export default class extends Vue {
        @Prop({ default: null }) data: object[];
        @Prop({ default: () => [] }) columns: string[];

        columnNames: string[] = null;

        @Watch('data')
        onDataChanged() {
            if (this.columns) {
                this.columnNames = this.columns;
            } else if (this.data.length) {
                this.columnNames = Object.keys(this.data[0]);
            } else {
                this.columnNames = [];
            }
        }

        created() {
            this.onDataChanged()
        }
    }
</script>

<style scoped>

</style>
