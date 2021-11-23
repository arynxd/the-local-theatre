export type PermissionValue = number
export type PermissionLevel = 'none' | 'user' | 'moderator'

const LEVEL_TO_VALUE = new Map<PermissionLevel, PermissionValue>(
    [
        ['none', 0],
        ['user', 1],
        ['moderator', 2]
    ]
)

const VALUE_TO_LEVEL = new Map<PermissionValue, PermissionLevel>(
    [
        [0, 'none'],
        [1, 'user'],
        [2, 'moderator']
    ]
)

function throwNotExistsLevel(level: PermissionLevel): never {
    throw new TypeError('Permission level ' + level + ' did not exist in the map.')
}

function throwNotExistsValue(value: PermissionValue): never {
    throw new TypeError('Permission value ' + value + ' did not exist in the map.')
}

export function hasPermission(perms: PermissionValue, level: PermissionLevel): boolean {
    const val = LEVEL_TO_VALUE.get(level)
    return !!val && val <= perms
}

export function toValue(level: PermissionLevel): PermissionValue {
    return LEVEL_TO_VALUE.get(level) ?? throwNotExistsLevel(level)
}

export function toLevel(value: PermissionValue): PermissionLevel {
    return VALUE_TO_LEVEL.get(value) ?? throwNotExistsValue(value)
}

