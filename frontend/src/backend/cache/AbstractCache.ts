import { EntityIdentifier } from '../../model/EntityIdentifier'
import { IdentifiedEntity } from '../../model/IdentifiedEntity'

/**
 * An extension of the inbuilt Map, which adds extra functionality
 */
export abstract class AbstractCache<V extends IdentifiedEntity> extends Map<
    EntityIdentifier,
    V
> {
    public setAll(elements: [EntityIdentifier, V][]) {
        for (const el of elements) {
            this.set(el[0], el[1])
        }
    }
}
