/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save() {
	return (
		<div { ...useBlockProps.save() }>
			<div className="outer-cal-box">
				Pick a date:<span id="pick-a-date-holder"></span>
			<div className="cpj-calendar-box">Loading...	</div>
			</div>
			<div className="outer-time-box">
				<span id="pick-a-time-text">Pick a time:</span><span id="pick-a-time-holder"></span>
			<div className="cpj-time-box"></div>
			</div>
		</div>
	);
}

