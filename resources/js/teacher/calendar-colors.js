// ── Color utilities ───────────────────────────────────
export const PALETTE = [
    "#1a5276",
    "#6c3483",
    "#2e7d56",
    "#a0522d",
    "#0e7490",
    "#c0392b",
    "#455a64",
    "#7d6608",
    "#117a65",
    "#784212",
];

export function seededColor(courseId) {
    // MurmurHash3 finalizer — excellent avalanche for small integers
    let h = courseId;
    h = Math.imul(h ^ (h >>> 16), 0x85ebca6b) >>> 0;
    h = Math.imul(h ^ (h >>> 13), 0xc2b2ae35) >>> 0;
    h = (h ^ (h >>> 16)) >>> 0;
    return PALETTE[h % PALETTE.length];
}
