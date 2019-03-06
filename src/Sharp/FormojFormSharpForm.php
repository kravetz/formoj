<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Form;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormDateField;
use Code16\Sharp\Form\Fields\SharpFormListField;
use Code16\Sharp\Form\Fields\SharpFormMarkdownField;
use Code16\Sharp\Form\Fields\SharpFormTextareaField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Layout\FormLayoutFieldset;
use Code16\Sharp\Form\SharpForm;

class FormojFormSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    function buildFormFields()
    {
        $this->addField(
            SharpFormTextField::make("title")
                ->setLabel(trans("formoj::sharp.forms.fields.title.label"))
        )->addField(
            SharpFormMarkdownField::make("description")
                ->setLabel(trans("formoj::sharp.forms.fields.description.label"))
                ->setToolbar([
                    SharpFormMarkdownField::B, SharpFormMarkdownField::I,
                    SharpFormMarkdownField::SEPARATOR,
                    SharpFormMarkdownField::A,
                ])
                ->setHeight(200)
        )->addField(
            SharpFormMarkdownField::make("success_message")
                ->setLabel(trans("formoj::sharp.forms.fields.success_message.label"))
                ->setToolbar([
                    SharpFormMarkdownField::B, SharpFormMarkdownField::I,
                    SharpFormMarkdownField::SEPARATOR,
                    SharpFormMarkdownField::A,
                ])
                ->setHeight(200)
                ->setHelpMessage(trans("formoj::sharp.forms.fields.success_message.help_text"))
        )->addField(
            SharpFormDateField::make("published_at")
                ->setLabel(trans("formoj::sharp.forms.fields.published_at.label"))
                ->setHasTime(true)
                ->setDisplayFormat("DD/MM/YYYY HH:mm")
        )->addField(
            SharpFormDateField::make("unpublished_at")
                ->setLabel(trans("formoj::sharp.forms.fields.unpublished_at.label"))
                ->setHasTime(true)
                ->setDisplayFormat("DD/MM/YYYY HH:mm")
        )->addField(
            SharpFormListField::make("sections")
                ->setLabel(trans("formoj::sharp.forms.fields.sections.label"))
                ->setAddable()->setAddText(trans("formoj::sharp.forms.fields.sections.add_label"))
                ->setRemovable()
                ->setSortable()->setOrderAttribute("order")
                ->addItemField(
                    SharpFormTextField::make("title")
                        ->setLabel(trans("formoj::sharp.forms.fields.sections.fields.title.label"))
                )
                ->addItemField(
                    SharpFormTextareaField::make("description")
                        ->setLabel(trans("formoj::sharp.forms.fields.sections.fields.description.label"))
                        ->setRowCount(3)
                )
        );
    }

    /**
     * Build form layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    function buildFormLayout()
    {
        $this->addColumn(6, function (FormLayoutColumn $column) {
            $column
                ->withSingleField("title")
                ->withFieldset("Dates de publication (facultatif)", function (FormLayoutFieldset $fieldset) {
                    $fieldset->withFields("published_at|6", "unpublished_at|6");
                })
                ->withSingleField("description")
                ->withSingleField("success_message");

        })->addColumn(6, function (FormLayoutColumn $column) {
            $column
                ->withSingleField("sections", function(FormLayoutColumn $column) {
                    $column
                        ->withSingleField("title")
                        ->withSingleField("description");
                });
        });
    }

    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    function find($id): array
    {
        return $this->transform(Form::with("sections")->findOrFail($id));
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    function update($id, array $data)
    {
        $form = $id ? Form::findOrFail($id) : new Form();

        $this->save($form, $data);

        return $form->id;
    }

    /**
     * @param $id
     */
    function delete($id)
    {
        Form::findOrFail($id)->delete();
    }
}