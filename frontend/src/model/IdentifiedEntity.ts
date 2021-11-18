import {EntityIdentifier} from "./EntityIdentifier";
import {GenericModel} from "./GenericModel";

export interface IdentifiedEntity extends GenericModel {
    id: EntityIdentifier
}