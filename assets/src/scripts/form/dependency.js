export default class dependency {

    constructor( field ) {

        this.$ = field;

        // rules
        this.rules = field.data('dependency')
        if( typeof this.rules !== 'object' ) {
            return
        }

        this.single = !! this.rules.id

        this.relation = this.rules.relation || 'and'
        this.style = this.rules.style || 'rz-none'

        delete this.rules.relation
        delete this.rules.style

        // single
        if( this.single ) {
            this.rules = {
                0: this.rules
            }
        }

        // form stack
        this.form = this.$.parent('.rz-repeater-content')
		if( ! this.form.length ) {
			this.form = this.$.closest('.rz-form')
		}

        this.init()
        this.trigger()

    }

    init() {

        let compared = false, and = true, or = false

        for( var i in this.rules ) {

            compared = this.compare( this.rules[i] )

            if( ! compared ) { and = false; }
            if( compared ) { or = true; }

        }

        if( this.relation.toLowerCase() == 'and' ) {
            this.toggle( and )
        }else{
            this.toggle( or )
        }

    }

    trigger() {

        for( var i in this.rules ) {

            $('.rz-field[data-id="' + this.rules[i].id + '"]', this.form).on('rz-field:changed', () => {
                this.init()
            });

        }

    }

    compare( rule ) {

        let dep_field = $('.rz-field[data-id="' + rule.id + '"]', this.form)
		let dep_input = $('input, select, textarea', dep_field).not(':disabled').filter(( index, element ) => {
            return new RegExp(`(rz_)?${rule.id}(\[\])?`, 'g').test( $(element).attr('name') )
        })

		let dep_value = null;

        switch( dep_input.attr('type') ) {
            case 'radio':
                dep_value = dep_input.filter(( index, element ) => { return $(element).is(':checked') }).val(); break;
            default:
                dep_value = dep_input.val()
        }

        // multiple
		if( Array.isArray( rule.value ) ) {

			let multiple_compare = false

			for( let i = 0; i < rule.value.length; i++ ) {
				if( this.comparison( rule.compare, dep_value, rule.value[i] ) ) {
					multiple_compare = true; break
				}
			}

			return multiple_compare

		}
		// single
		else{

			return this.comparison( rule.compare, dep_value, rule.value )

		}

        return false

    }

    comparison( compare, dep_value, value ) {

        compare = compare || '='
        dep_value = dep_value || ''

		switch( compare ) {
			case '=':
				return dep_value == value; break
			case '!=':
				return dep_value != value; break
			case '<':
				return dep_value < value; break
			case '>':
				return dep_value > value; break
			case '<=':
				return dep_value <= value; break
			case '>=':
				return dep_value >= value; break
			case 'IN':
				return dep_value.indexOf( value ) != -1; break
			case 'NOT IN':
				return dep_value.indexOf( value ) == -1; break
			default:
				return false
		}

	}

    toggle( compared ) {

        compared = compared || false

        if( compared ) {
			this.$.removeClass( this.style ).removeClass('rz-no-pointer');
		}else{
			this.$.addClass( this.style ).addClass('rz-no-pointer');
		}

	}

}
