<template>
    <form class="edit-user">
        <div class="form__control">
            <input type="text" class="edit-user__username input" v-model="user.name">
        </div>

        <div class="form__control">
            <textarea type="text" class="input"></textarea>
        </div>

        <select>
            <option value="Male"></option>
            <option value="Male"></option>
            <option value="Male"></option>
        </select>

        <button class="btn btn--primary">Save</button>
    </form>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";
    import {dto} from "../../store/dto";
    import UserDto = dto.UserDto;

    @Component
    export default class EditUserForm extends Vue {
        @Prop({ required: true }) user: UserDto
    }
</script>

<style scoped lang="scss">
    .edit-user {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 10px 0 0 15px;

        &__username {
            font-size: 2em;
        }
    }

    .input {
        border: 1px solid transparent !important;
        background: rgba(0, 0, 0, 0.01);

        &:hover {
            border-color: #aaa !important;
        }

        &:focus {
            background: rgba(0, 0, 0, 0.1);
        }
    }
</style>
