class Button
{
    constructor(scene,x,y,sizex,sizey,text,style,background,call,entrance) {
        this.parent = scene;
        this.x = x;
        this.y = y;
        this.sizex = sizex;
        this.sizey = sizey;
        this.text = text;
        this.style = style;
        this.background = background;
        this.call = call;
        this.borders = [];
        this.init();
    }

    init(){
        this.backgroundColor = this.parent.add.image(this.x,this.y,"block").setOrigin(0,0);
        this.backgroundColor.displayWidth = this.sizex;
        this.backgroundColor.displayHeight = this.sizey;
        if (this.background !== 0){
            this.backgroundColor.setTint(this.background);
        }else{
            this.backgroundColor.setAlpha(0);
        }
        this.parent.add.text(this.x+this.sizex/2,this.y,this.text,this.style).setOrigin(0.5,0);

        //add hover border
        this.borderTop = this.parent.add.image(this.x,this.y,"block").setOrigin(0,0);
        this.borderTop.displayWidth = this.sizex;
        this.borderTop.displayHeight = 3;
        this.borderBottom = this.parent.add.image(this.x,this.y+this.sizey-3,"block").setOrigin(0,0);
        this.borderBottom.displayWidth = this.sizex;
        this.borderBottom.displayHeight = 3;
        this.borderLeft = this.parent.add.image(this.x,this.y,"block").setOrigin(0,0);
        this.borderLeft.displayWidth = 3;
        this.borderLeft.displayHeight = this.sizey;
        this.borderRight = this.parent.add.image(this.x+this.sizex-3,this.y,"block").setOrigin(0,0);
        this.borderRight.displayWidth = 3;
        this.borderRight.displayHeight = this.sizey;
        this.borders = [this.borderTop,this.borderBottom,this.borderLeft,this.borderRight];
        for (let i=0;i<this.borders.length;i++){
            this.borders[i].setAlpha(0);
        }
        this.parent.input.on('gameobjectover',function(pointer, gameobject){
            console.log(gameobject);
            if (gameobject === this.backgroundColor){
                for (let i=0;i<this.borders.length;i++){
                    this.tweens.add({  
                        targets: this.borders[i],
                        alpha: 1,
                        duration: 1000, 
                        ease: 'Sine.easeInOut',  
                        repeat: 0,
                    }); 
                }
            }
        },this);

    }

    clicked(){
        this.call();
    }
}