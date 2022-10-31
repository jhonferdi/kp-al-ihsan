# Form Wizard

A simple wizard to split your forms easier

Form Wizard is a vue based component with dependency using Tailwind css.


### Example

```html
<FormWizard>
  <StepItem>Form 1</StepItem>
  <StepItem>Form 2</StepItem>
  <StepItem>Form 3</StepItem>
  <StepItem>Form 4</StepItem>
</FormWizard>
```


#### Access Properties and Methods

You can access child properties and methods on parents using refs, see below:

```html
<FormWizard ref="wizard">
  <StepItem>...</StepItem>
  <StepItem>...</StepItem>
  <StepItem>...</StepItem>
</FormWizard>

```
and call it on parent's method

```javascript
// call child properties and methods
export default{
  methods:{
    callChildMethod(){
      let currentstep = this.$refs.wizard.currentstep;
      this.$refs.wizard.stepChanged(3);
    }
  }
}
```

## License
Copyright (c) 2022 Andri