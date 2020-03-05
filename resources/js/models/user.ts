import {IDModel, IIDModel} from "./model";

interface IUser extends IIDModel {

}

export default class User extends IDModel<IUser> {

}
