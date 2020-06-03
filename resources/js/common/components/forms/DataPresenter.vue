<template>
    <div class="data-presenter" :class="{selectable}">
        <search-bar v-if="searchable" v-model="query" @search="$emit('search', query)" />

        <pagination-control :pagination="pagination" @requestPage="$emit('requestPage', $event)">
            <template v-slot:body="{items}">
                <table>
                    <tr>
                        <slot name="table-header">
                            <template v-if="items.length !== 0">
                                <th v-for="k of Object.keys(items[0]).sort()">
                                    {{ toSentence(k) }}
                                </th>
                            </template>
                        </slot>
                    </tr>

                    <tr class="item" v-for="u in items" @click="selectable ? $emit('selected', u) : null">
                        <slot v-bind="u">
                            <td v-for="k in Object.keys(u).sort()">
                                <bool-presenter v-if="typeof u[k] === 'boolean'" :value="u[k]" />
                                <template v-else>
                                    {{ u[k] }}
                                </template>
                            </td>
                        </slot>
                    </tr>
                </table>
            </template>
        </pagination-control>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, dto, Watch, Prop} from "@common";
    import SearchBar from "@common/components/forms/SearchBar.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import PaginationControl from "@common/components/utils/PaginationControl.vue";
    import BoolPresenter from "@common/components/forms/BoolPresenter.vue";

    @Component({
        name: "DataPresenter",
        components: {BoolPresenter, PaginationControl, SearchBar}
    })
    export default class DataPresenter extends AdminStoreComponent {
        @Prop({type: Boolean, default: false}) selectable: boolean;
        @Prop({type: Boolean, default: false}) searchable: boolean;
        @Prop() pagination: dto.PaginationDto<dto.UserDto>

        query = null;

        toSentence(v: string) {
            let result = v.replace( /([A-Z])/g, " $1" );
            return result.charAt(0).toUpperCase() + result.slice(1);
        }
    }
</script>

<style scoped lang="scss">
    .data-presenter {
        &.selectable {
            tr.item {
                &:hover {
                    outline: 2px solid #333;
                    outline-offset: 2px;
                    cursor: pointer;
                }
            }
        }
    }
</style>
