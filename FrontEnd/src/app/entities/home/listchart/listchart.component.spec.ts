import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ListchartComponent } from './listchart.component';

describe('ListchartComponent', () => {
  let component: ListchartComponent;
  let fixture: ComponentFixture<ListchartComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ListchartComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListchartComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
